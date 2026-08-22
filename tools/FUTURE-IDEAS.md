# Future ideas

Things worth doing that aren't high enough priority right now. Add to the
list as they come up; move an item out when it's done. (Block ideas live in
`BLOCK-IDEAS.md`.)

## Menu

- **Add images to the menu page.** Options, roughly in order of effort:
  - a single hero/banner photo above the Menu block (already possible: put
    an Image block before it — just needs a good photo);
  - an optional `image` per section shown beside the section title;
  - an optional small thumbnail per dish (square crop, left of the name),
    with the block switching to a one-column "card" layout when any dish
    has one — the current two-column text layout is too narrow for photos;
  - a photo strip between sections using the Carousel or a Photo grid block.
  The per-dish thumbnail is the one the café would most likely want, and
  the one that needs a design pass.
- Menu import: let the café choose *which* changes to apply (tick boxes on
  the review screen) rather than all-or-nothing.
- Menu import: read a photo of the printed menu (JPEG/PNG) as well as a PDF.
- Drinks / cakes sections once the café has a printed version of them.

## Site Details

- Move opening hours to a structured repeater (day ranges + times) so the
  footer, the Menu block's hours panel and a future Opening Hours block all
  read from one place, and JSON-LD `openingHours` can be emitted for Google.

## Housekeeping

- `preview/` is empty: either rebuild `preview/index.html` from the theme
  markup or drop the "Static preview" section from the README.
- Replace `screenshot.png` with a real theme screenshot.
