# Rise & Flour theme — working notes for Claude

## SCSS build

- `src/css/main.css` (+ `main.css.map`) is compiled from `src/scss/` by Dart Sass.
  Edit the SCSS, never the CSS.
- At the start of a dev session **one of us runs `cd src && npm run watch`**
  (Node is installed locally) and leaves it running. It recompiles on every
  save, so after that **do not compile the SCSS yourself** — no `npm run build`,
  no Docker sass, nothing. Just edit the `.scss` and the CSS follows.
- If a change doesn't show up, ask whether the watcher is running rather than
  building manually.
- The Docker sass command in the README is only a fallback for a machine
  without Node.
