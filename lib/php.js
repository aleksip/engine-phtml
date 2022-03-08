'use strict';

const path = require('path');

const php = {
  renderString(templateString, data, jsonFileData) {
    const args = '--string=' + this.doEncode(templateString);
    return this.doRender(args, data, jsonFileData);
  },

  renderFile(templateFile, data, jsonFileData) {
    const args = '--file=' + templateFile;
    return this.doRender(args, data, jsonFileData);
  },

  doEncode(string) {
    const buffer = new Buffer.from(string);
    return buffer.toString('base64');
  },

  doRender(args, data, jsonFileData) {
    const renderer = path.join(__dirname, 'render.php')
    const cmd = 'php ' + renderer + ' ' + args + ' --data=' + this.doEncode(JSON.stringify(data))
      + ' --jsonFileData=' + this.doEncode(JSON.stringify(jsonFileData));
    const exec = require('child_process').exec;
    return new Promise((resolve, reject) => {
      exec(cmd, (error, stdout, stderr) => {
        if (error) {
          console.warn(error);
        }
        resolve(stdout ? stdout : stderr);
      });
    });
  }
}

module.exports = php;
