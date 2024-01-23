const path = require('path')
const glob = require('glob')
const fs = require('fs-plus')
const fontello = require('fontello-cli/lib/fontello')
const sourcePath = path.join(process.cwd(), 'dist/config/*.json')
const targetPath = path.join(process.cwd(), 'dist/config.json')
const fontsPath = path.join(process.cwd(), `fonts/${process.env.STYLE}`)
const cssTempPath = path.join(process.cwd(), 'dist/')

if (!fs.existsSync(fontsPath)) {
  fs.mkdirSync(fontsPath, { recursive: true })
}

glob(sourcePath, (err, files) => {
  const allFilesJSONArray = files.map((f) => {
    return JSON.parse(fs.readFileSync(f))
  })

  const allFilesJSON = {
    ...allFilesJSONArray[0],
    name: `unicons-${process.env.STYLE}`,
  }

  allFilesJSONArray.forEach((j) => {
    allFilesJSON.glyphs = [...allFilesJSON.glyphs, ...j.glyphs]
  })

  console.log(`Merging total ${allFilesJSONArray.length} configs.`)
  fs.writeFileSync(targetPath, JSON.stringify(allFilesJSON), 'utf-8')

  // Remove Fontello Session
  const fontelloSession = path.join(process.cwd(), '.fontello-session')
  if (fs.existsSync(fontelloSession)) {
    fs.unlinkSync(fontelloSession)
  }

  fontello.install({
    config: targetPath,
    css: cssTempPath,
    font: fontsPath,
  })
})
