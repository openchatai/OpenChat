const fs = require('fs')
const path = require('path')
const axios = require('axios')
const eachLimit = require('async/eachLimit')
const uniq = require('lodash/uniq')
const filter = require('lodash/filter')
const sortBy = require('lodash/sortBy')
const maxBy = require('lodash/maxBy')
const upperFirst = require('lodash/upperFirst')

const countDuplicates = require('../utils/countDuplicates')
const downloadImage = require('../utils/downloadImage')

const targetPath = path.join(process.cwd(), `json/${process.env.STYLE}.json`)
const targetImagePath = path.join(process.cwd(), `svg/${process.env.STYLE}`)

const existingConfig = JSON.parse(
  fs.readFileSync(
    path.join(process.cwd(), `json/${process.env.STYLE}.json`),
    'utf-8',
  ),
)

const url = process.env[`API_DOWNLOAD_${process.env.STYLE.toUpperCase()}`]
const breakOnError = true

const existingMaxIcon = maxBy(existingConfig, 'code')
let startCharCode = existingMaxIcon ? existingMaxIcon.code + 1 : 59392

if (!fs.existsSync(path.join(process.cwd(), 'json'))) {
  fs.mkdirSync(path.join(process.cwd(), 'json'))
}

console.log(
  `Download SVGs in ${process.cwd()}. Next Starting Char Code ${startCharCode}, Unicode ${startCharCode.toString(
    16,
  )}.`,
)

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.withCredentials = true

const response = axios.get(url).then((response) => {
  const data = []
  const icons = response.data.response.unicons.map((item) => ({
    ...item,
    allTags: item.name,
    name: item.tags[item.tags.length - 1],
  }))

  const names = icons.map((icon) => icon.name)
  const uniqueNames = uniq(names)
  const repeated = countDuplicates(names)
  const duplicates = filter(repeated, (item) => item.count > 1)

  if (duplicates.length && breakOnError) {
    console.log(
      `Total Icons: ${names.length}, Unique Names: ${uniqueNames.length}`,
    )

    console.log(`${process.env.STYLE} Duplicates:`, duplicates)

    let dupFiles = []
    duplicates.forEach((d) => {
      dupFiles = [...dupFiles, ...filter(icons, { name: d.value })]
    })

    fs.writeFileSync(
      `${process.env.STYLE}-duplicates.json`,
      JSON.stringify(dupFiles),
      'utf-8',
    )

    throw new Error('There are duplicate files')
  }

  // Download All the icons from Iconscout
  eachLimit(
    icons,
    50,
    async (row) => {
      const url = row.svg
      // const ext = url.indexOf('.gif') === -1 ? 'jpg' : 'gif'
      const name = row.name
      const fileName = `${name}.svg`
      const filePath = path.resolve(targetImagePath, fileName)

      try {
        await downloadImage(url, filePath)

        const charCodeExists = existingConfig.find((i) => i.name === name)
        const charCode =
          charCodeExists && charCodeExists.code
            ? charCodeExists.code
            : startCharCode++

        data.push({
          uuid: row.uuid,
          id: row.id,
          name: name,
          svg: `svg/${process.env.STYLE}/${fileName}`,
          category: row.category,
          style: upperFirst(process.env.STYLE),
          tags: row.tags,
          code: charCode,
          unicode: charCode.toString(16),
          pro: Boolean(row.price),
        })
      } catch (error) {
        console.error(error)
        console.log('Error Downloading:', name)
      }
    },
    (err, results) => {
      if (err) {
        console.log(results)
        throw err
      }

      console.log(`${data.length} Images Downloaded!`)
      // Save the Airtable data as json
      fs.writeFileSync(
        targetPath,
        JSON.stringify(sortBy(data, 'name')),
        'utf-8',
      )

      // console.log(`New Data saved from Airtable to ${targetPath}!`)
    },
  )
})
