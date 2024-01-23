const fs = require('fs');

// Parse package.json
const packageFile = './package.json';
const packageText = fs.readFileSync(packageFile, 'utf8');
const packageJson = JSON.parse(packageText);
const packageVersion = packageJson.version;
// Check for preview.html
const previewFile = './preview.html';
if (!fs.existsSync(previewFile)) {
    throw new Error('Error: preview.html must exist!');
}
const previewText = fs.readFileSync(previewFile, 'utf8');
const parts = previewText.match(/<span class="version">([^<]+)<\/span>/);
if (parts === null) {
    // Did you modify preview.html file ???
    throw new Error('Error: preview.html version string not found!');
}
// Never include a index.html file!
const indexFile = './index.html';
if (fs.existsSync(indexFile)) {
    throw new Error('Error: index.html should not exist, only preview.html');
}
const previewVersion = parts[1];
if (packageVersion != previewVersion) {
    // Not good, almost published the wrong version
    throw new Error(`Error: package "${packageVersion}" != preview.html "${previewVersion}"`);
}
// Verify SCSS Version
const scssVariablesFile = './scss/_variables.scss';
const scssVariablesText = fs.readFileSync(scssVariablesFile, 'utf8');
const vParts = scssVariablesText.match(/"(\d+).(\d+).(\d+)" !default;/);
if (vParts === null) {
    throw new Error('Error: Could not parse SCSS version!');
}
const scssVersion = `${vParts[1]}.${vParts[2]}.${vParts[3]}`;
if (packageVersion != scssVersion) {
    // Not good, almost published the wrong version
    throw new Error(`Error: package "${packageVersion}" != scss/variables.scss "${previewVersion}"`);
}
console.log(`Success: ${packageVersion} looks good!`);
