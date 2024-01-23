const fs = require('fs')
const axios = require('axios')

const downloadImage = async (url, path, parseSVG) => {
  // console.log(`Downloading Image: ${url}`)
  // axios image download with response type "stream"
  const response = await axios({
    method: 'GET',
    url: url
  })

  // Replace extra characters such as new lines, tabs from file
  let svg = response.data.replace(/\r+|\n+|\t+/gm, '')

  // If we've to modify svg before saving
  if (parseSVG) {
    svg = await parseSVG(svg)
  }

  fs.writeFileSync(path, svg, 'utf-8')
}

module.exports = downloadImage
