import os, zipfile

ROOT = os.path.dirname(os.path.abspath(__file__))
OUT_ZIP = os.path.join(ROOT, "deploy-final.zip")
EXCLUDE_DIRS = {'.git', 'node_modules', 'tests', 'tmp_export', '.vscode', '.claude', 'gambar-produk'}
EXCLUDE_FILES = {'deploy-final.zip', 'build.log', '.env'}

print("Build deploy-final.zip ...")
# fix public/storage symlink -> real dir
pub_storage = os.path.join(ROOT, "public/storage")
if os.path.islink(pub_storage):
    os.remove(pub_storage)
os.makedirs(pub_storage, exist_ok=True)
gi = os.path.join(pub_storage, ".gitignore")
if not os.path.exists(gi):
    open(gi, "w").write("*\n!.gitignore\n")

with zipfile.ZipFile(OUT_ZIP, 'w', zipfile.ZIP_DEFLATED, compresslevel=9) as zf:
    env_src = os.path.join(ROOT, ".env.infinityfree")
    if os.path.exists(env_src):
        zf.write(env_src, ".env")
    for root, dirs, files in os.walk(ROOT):
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
        rel_root = os.path.relpath(root, ROOT)
        if rel_root == ".":
            rel_root = ""
        for f in files:
            if f in EXCLUDE_FILES:
                continue
            if f.startswith(".env.") or f == ".env.example":
                continue
            full = os.path.join(root, f)
            arc = os.path.join(rel_root, f).replace("\\", "/")
            if arc == ".env":
                continue
            if arc.startswith("storage/logs/") or arc.startswith("storage/framework/cache/"):
                continue
            if full == OUT_ZIP:
                continue
            zf.write(full, arc)

print(f"Selesai: {os.path.getsize(OUT_ZIP)/1024/1024:.2f} MB -> {OUT_ZIP}")
