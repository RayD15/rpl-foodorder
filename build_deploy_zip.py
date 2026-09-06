import zipfile, os, sys

PROJECT = r"C:/FILE Rayhand Ayandrie/WEB KDI"
OUT_ZIP = os.path.join(PROJECT, "deploy-final.zip")

# Excluded top-level dirs (and any dir by this name recursively)
EXCLUDE_DIRS = {'.git', 'node_modules', 'tests', 'tmp_export', '_staging',
                'deploy-infinityfree', '.vscode', '.idea', '.claude',
                'gambar-produk'}
# Files to never include
EXCLUDE_NAMES = {'deploy-prod.env', 'deploy-htaccess.htaccess', '.DS_Store',
                 'build.log', 'opencode.json', 'render.yaml', 'Dockerfile',
                 'docker-entrypoint.sh', 'PRD.md', 'AGENTS.md',
                 'build_deploy_zip.py', 'PANDUAN-DEPLOY.md',
                 '.phpunit.result.cache', 'qrcode-pesendong.png'}

# Production files to inject (read once)
with open(os.path.join(PROJECT, '.env.production'), 'rb') as f:
    PROD_ENV = f.read()
with open(os.path.join(PROJECT, '.htaccess'), 'rb') as f:
    PROD_HTACCESS = f.read()

def skip_dir_rel(rp):
    parts = set(rp.split('/'))
    return bool(parts & EXCLUDE_DIRS)

if os.path.exists(OUT_ZIP):
    os.remove(OUT_ZIP)

count = 0
with zipfile.ZipFile(OUT_ZIP, 'w', zipfile.ZIP_DEFLATED, compresslevel=9) as zf:
    # Inject production .env and .htaccess
    zf.writestr('.env', PROD_ENV)
    zf.writestr('.htaccess', PROD_HTACCESS)
    count += 2

    for root, dirs, files in os.walk(PROJECT):
        # prunes
        keep_dirs = []
        for d in dirs:
            rp = os.path.relpath(os.path.join(root, d), PROJECT).replace('\\', '/')
            if not skip_dir_rel(rp):
                keep_dirs.append(d)
        dirs[:] = keep_dirs

        for f in files:
            if f in EXCLUDE_NAMES:
                continue
            fp = os.path.join(root, f)
            rel = os.path.relpath(fp, PROJECT).replace('\\', '/')
            # NEVER zip the output archive itself (it lives inside the project root)
            if f == os.path.basename(OUT_ZIP):
                continue
            # root .env/.htaccess are injected from production files; skip walk duplicates
            if rel in ('.env', '.htaccess'):
                continue
            # any other .env.* variants (tidb, production, koyeb) must NOT ship
            if f.startswith('.env.') or f in ('.env.example',):
                continue
            # skip storage framework cache/logs that shouldn't deploy
            if rel.startswith('storage/logs/') or rel.startswith('storage/framework/cache/') \
               or rel.startswith('storage/framework/sessions/'):
                continue
            zf.write(fp, rel)
            count += 1

    # public/storage/.gitignore + products/.gitignore are REAL files in the tree — walked normally.
    # No injection needed (avoids duplicate-name warnings).

print('WROTE', OUT_ZIP)
print('ENTRIES', count)
print('SIZE_MB', round(os.path.getsize(OUT_ZIP)/1024/1024, 2))

# Verification: top-level entries + compress types + extract version
with zipfile.ZipFile(OUT_ZIP, 'r') as zf:
    toplevel = {}
    types = {}
    max_ver = 0
    for i in zf.infolist():
        t = i.filename.split('/')[0]
        toplevel[t] = toplevel.get(t, 0) + 1
        types[i.compress_type] = types.get(i.compress_type, 0) + 1
        max_ver = max(max_ver, i.extract_version)
    print('TOPLEVEL_ENTRIES', dict(sorted(toplevel.items())))
    print('COMPRESS_TYPES', types)
    print('MAX_EXTRACT_VERSION', max_ver)
