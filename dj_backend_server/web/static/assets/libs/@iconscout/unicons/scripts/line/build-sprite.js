const svgstore = require('svgstore')
const fs = require('fs-plus')
const glob = require('glob')
const path = require('path')
const sprites = svgstore()
const svgFiles = path.join(process.cwd(), `svg/${process.env.STYLE}/*svg`)

if (!fs.existsSync(path.join(process.cwd(), 'sprite'))) {
  fs.mkdirSync(path.join(process.cwd(), 'sprite'))
  fs.mkdirSync(path.join(process.cwd(), `sprite/${process.env.STYLE}`))
}

let writtenFiles = 0

glob(svgFiles, {}, function (er, files) {
  files.forEach(function (file) {
    const filename = path.basename(file).replace('.svg', '')
    sprites.add(filename, fs.readFileSync(file, 'utf8'))

    writtenFiles += 1
  })

  fs.writeFile(path.join(process.cwd(), `sprite/${process.env.STYLE}/unicons.svg`), sprites, (err) => {
    if (err) console.log(err)
  })

  console.log(`Wrote Sprite for ${writtenFiles} files`)
})
