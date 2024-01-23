const path = require('path')
const glob = require('glob')
const fs = require('fs-plus')
const fontello = require('fontello-cli/lib/fontello')
const sourcePath = path.join(process.cwd(), 'dist/config/*.json')
const fontsPath = path.join(process.cwd(), `fonts/${process.env.STYLE}`)
const cssTempPath = path.join(process.cwd(), 'dist/')
const cssPath = path.join(process.cwd(), `css/${process.env.STYLE}.css`)

const cssBefore = fs
  .readFileSync(path.join(process.cwd(), 'css/before.css'), 'utf-8')
  .replace(/\{CSS_PREFIX\}/g, process.env.CSS_PREFIX)
  .replace(/\{STYLE\}/g, process.env.STYLE)
const cssFontFaceList = []
let cssCodesList = []

const msleep = (n) => {
  Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, n)
}

if (!fs.existsSync(fontsPath)) {
  fs.mkdirSync(fontsPath, { recursive: true })
}

glob(sourcePath, (err, files) => {
  files.forEach((file) => {
    console.log(`Generating ${process.env.STYLE} Font for ${file}`)

    // Remove Fontello Session
    const fontelloSession = path.join(process.cwd(), '.fontello-session')
    if (fs.existsSync(fontelloSession)) {
      fs.unlinkSync(fontelloSession)
    }

    fontello.install({
      config: file,
      css: cssTempPath,
      font: fontsPath,
    })

    // Append Font Face
    const configData = JSON.parse(fs.readFileSync(file, 'utf-8'))
    const allowedChars = configData.glyphs.map((g) => g.code)
    const firstChar = allowedChars[0].toString(16)
    const lastChar = allowedChars[allowedChars.length - 1].toString(16)

    cssFontFaceList.push(`@font-face {
  font-family: 'unicons-${process.env.STYLE}';
  src: url('../fonts/${process.env.STYLE}/${configData.name}.eot');
  src: url('../fonts/${process.env.STYLE}/${
      configData.name
    }.eot#iefix') format('embedded-opentype'),
        url('../fonts/${process.env.STYLE}/${
      configData.name
    }.woff2') format('woff2'),
        url('../fonts/${process.env.STYLE}/${
      configData.name
    }.woff') format('woff'),
        url('../fonts/${process.env.STYLE}/${
      configData.name
    }.ttf') format('truetype'),
        url('../fonts/${process.env.STYLE}/${
      configData.name
    }.svg#unicons') format('svg');
  font-weight: normal;
  font-style: normal;
  unicode-range: U+${firstChar.toUpperCase()}-${lastChar.toUpperCase()};
}`)

    cssCodesList = [
      ...cssCodesList,
      ...configData.glyphs.map(
        (g) =>
          `.${process.env.CSS_PREFIX}-${
            g.css
          }:before { content: '\\${g.code.toString(16)}'; }`,
      ),
    ]

    msleep(3000)
  })

  // Write `unicons.css` file
  const cssUnicons = `${cssFontFaceList.join(
    '\n',
  )}${cssBefore}${cssCodesList.join('')}`
  fs.writeFileSync(cssPath, cssUnicons, 'utf-8')
})
