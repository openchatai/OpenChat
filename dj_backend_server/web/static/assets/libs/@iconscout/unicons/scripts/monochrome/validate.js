const path = require('path')
const glob = require('glob')
const fs = require('fs-plus')
const sourcePath = path.join(process.cwd(), 'dist/test', '**/*.svg')
const replaceFill = require('./replaceFill')

glob(sourcePath, function (err, files) {
  if (err) {
    console.log(err)
    return false
  }

  files = files.map((f) => path.normalize(f))

  files.forEach(filename => {
    const svg = fs.readFileSync(filename, 'utf-8')
    replaceFill(svg, filename)
  })
})
