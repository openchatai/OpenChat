const ttf2woff2 = require('ttf2woff2')
const path = require('path')
const fs = require('fs-plus')
const glob = require('glob')

const sourcePath = path.join(process.cwd(), `fonts/${process.env.STYLE}/*.ttf`)
const fontsPath = path.join(process.cwd(), `fonts/${process.env.STYLE}`)

glob(sourcePath, (err, files) => {
  files.forEach((file) => {
    const fontName = `${file.split('/').pop().split('.').shift()}.woff2`
    console.log(`Overwriting ${process.env.STYLE}: ${fontName}`)
    const input = fs.readFileSync(file)
    fs.writeFileSync(`${fontsPath}/${fontName}`, ttf2woff2(input))
  })
})
