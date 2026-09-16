# Plan: Navbar Label Visibility (Icon Only When Inactive)

## Goal
Modify the mobile bottom navigation bar so that unselected items show only their icons, while the selected (active) item shows both its icon and text label.

## Current Context / Assumptions
- File: `resources/views/layouts/customer.blade.php` defines the mobile navigation structure.
- File: `resources/css/app.css` defines styling and state classes (e.g., `.is-active`).
- Current behavior: Both icon and text (`[data-nav-label]`) are always visible for all items.
- Target behavior: Items without `is-active` class hide their text label; active items display it.

## Architecture / Proposed Approach
Use CSS rules targeting `[data-mobile-nav] a:not(.is-active) [data-nav-label]` to hide or collapse the text label, and `[data-mobile-nav] a.is-active [data-nav-label]` to reveal it, adding a smooth width/opacity transition for polish.

## Step-by-Step Tasks

### Task 1: Update CSS for Conditional Label Visibility
- File: `C:/FILE Rayhand Ayandrie/WEB KDI/resources/css/app.css`
- Action: Modify rules for `[data-mobile-nav] a [data-nav-label]` and `[data-mobile-nav] a.is-active [data-nav-label]`.
- Code snippet to add/replace:
```css
[data-mobile-nav] a [data-nav-label] {
    font-size: 0.65rem;
    font-weight: 500;
    color: var(--color-ink-400);
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
}

[data-mobile-nav] a.is-active [data-nav-label] {
    font-weight: 700;
    color: var(--color-ink-900);
    max-height: 1.2rem;
    opacity: 1;
    transform: translateY(-1px);
}
```

### Task 2: Rebuild Assets and Validate
- Command: `npm run build`
- Expected output: Vite bundle successfully compiled with no errors.
