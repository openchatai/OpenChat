const { exec } = require('child_process')
const axios = require('axios')
const path = require('path')
const fs = require('fs')

const pkg = require('../package.json')

exec(`npm view ${pkg.name} version`, async (err, out) => {
  const latestVersion = out.trim()

  const styles = ['solid']

  for (let index = 0; index < styles.length; index++) {
    const style = styles[index]

    const url = `https://unicons.iconscout.com/release-pro/v${latestVersion}/json/${style}.json`
    const response = await axios({
      method: 'GET',
      url,
    })

    const targetPath = path.join(process.cwd(), `json/${style}.json`)
    fs.writeFileSync(targetPath, JSON.stringify(response.data), 'utf-8')
  }
})
