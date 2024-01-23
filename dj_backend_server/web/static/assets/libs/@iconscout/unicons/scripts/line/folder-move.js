const fs = require('fs-plus')
const path = require('path')
const glob = require('glob')

const sourcePath = path.join(process.cwd(), 'fontello-*')
const destPath = path.join(process.cwd())

glob(sourcePath, function (err, files) {
  const fontFolder = files[0]
  console.log(fontFolder, destPath)

  // Keep Custom Files
  // i.e. Animations
  fs.renameSync(destPath + '/css/animation.css', fontFolder + '/css/animation.css', (err) => {
    if (err) throw err
    console.log('Animation.css moved!')
  })

  // Clear Directories
  fs.removeSync(destPath + '/font')
  fs.removeSync(destPath + '/css')
  fs.removeSync(destPath + '/index.html')

  // Move Font Files
  fs.rename(fontFolder + '/font', destPath + '/font', (err) => {
    if (err) throw err
    console.log('Fonts moved!')
  })

  // Move CSS Files
  fs.rename(fontFolder + '/css', destPath + '/css', (err) => {
    if (err) throw err
    console.log('CSS moved!')
  })

  // Move Demo File
  fs.rename(fontFolder + '/demo.html', destPath + '/index.html', (err) => {
    if (err) throw err
    console.log('Demo.html moved!')
  })
})
