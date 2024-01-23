const SVG_URL_BASE = `https://unicons.iconscout.com/${
  process.env.RELEASE_DIR || 'release'
}/${process.env.CI_COMMIT_REF_NAME}/svg/monochrome/`
const iconPrefix = 'uim-'

// Add Unicons Window
window.Unicons = window.Unicons || {}
window.Unicons.DEBUG = window.Unicons.DEBUG || false

const apply = (element) => {
  element.classList.forEach((className) => {
    if (className.includes(iconPrefix)) {
      fetchIconsAndReplace(
        className.toLocaleLowerCase().replace(iconPrefix, ''),
      )
    }
  })
}

const fetchIconsAndReplace = (iconName) => {
  fetch(`${SVG_URL_BASE}${iconName}.svg`)
    .then((res) => res.text())
    .then((svg) => replaceWithSVG(iconName, svg))
}

const replaceWithSVG = (name, svg) => {
  // Replace it with SVG
  const elements = document.getElementsByClassName(`${iconPrefix}${name}`)

  while (elements.length > 0) {
    const element = elements[0]
    const span = document.createElement('span')
    span.innerHTML = svg
    span.classList.add('uim-svg')
    span.firstChild.setAttribute('width', '1em')

    // Add existing styles to the element
    span.style.cssText = element.style.cssText

    // If user wants white bg rather than opacity
    if (element.classList.contains('uim-white')) {
      span.style.mask = `url(${SVG_URL_BASE}${name}.svg)`
      span.style.webkitMask = `url(${SVG_URL_BASE}${name}.svg)`
      span.style.background = 'white'
    }

    element.replaceWith(span)
  }
}

const replaceAllIcons = () => {
  const elements = document.getElementsByClassName('uim')
  const iconsToFetch = []

  if (window.Unicons.DEBUG) {
    console.log(`Replacing ${elements.length} icons`)
  }

  for (let i = 0; i < elements.length; i++) {
    const element = elements[i]
    element.classList.forEach((className) => {
      if (className.includes(iconPrefix)) {
        const iconName = className.toLocaleLowerCase().replace(iconPrefix, '')
        if (!iconsToFetch.includes(iconName)) {
          fetchIconsAndReplace(iconName)
          iconsToFetch.push(iconName)
        }
      }
    })
  }
}

const watch = () => {
  const insertionQ = require('insertion-query')
  insertionQ('.uim').every((element) => {
    apply(element)
    return element
  })
  window.Unicons.WATCHER = true
  if (window.Unicons.DEBUG) {
    console.log('Monochrome watcher started')
  }
}

const init = () => {
  replaceAllIcons()
  if (!window.Unicons.WATCHER) {
    watch()
  }
  if (window.Unicons.DEBUG) {
    console.log('Monochrome initiated')
  }
}

window.onload = init
window.Unicons.refresh = replaceAllIcons

// Append CSS
const style = document.createElement('style')
style.innerHTML = `:root {
  --uim-primary-opacity: 1;
  --uim-secondary-opacity: 0.70;
  --uim-tertiary-opacity: 0.50;
  --uim-quaternary-opacity: 0.25;
  --uim-quinary-opacity: 0;
}
.uim-svg {
  display: inline-block;
  height: 1em;
  vertical-align: -0.125em;
  font-size: inherit;
  fill: var(--uim-color, currentColor);
}
.uim-svg svg {
  display: inline-block;
}
.uim-primary {
  opacity: var(--uim-primary-opacity);
}
.uim-secondary {
  opacity: var(--uim-secondary-opacity);
}
.uim-tertiary {
  opacity: var(--uim-tertiary-opacity);
}
.uim-quaternary {
  opacity: var(--uim-quaternary-opacity);
}
.uim-quinary {
  opacity: var(--uim-quinary-opacity);
}`

document.head.appendChild(style)
