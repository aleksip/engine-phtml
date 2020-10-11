'use strict';

const path = require('path');

const php = {
  renderString(templateString, data) {
    const args = '--string=' + this.doEncode(templateString);
    return this.doRender(args, data);
  },

  renderFile(templateFile, data) {
    const args = '--file=' + templateFile;
    return this.doRender(args, data);
  },

  doEncode(string) {
    const buffer = new Buffer.from(string);
    return buffer.toString('base64');
  },

  doRender(args, data) {
    const renderer = path.join(__dirname, 'render.php')
    const cmd = 'php ' + renderer + ' ' + args + ' --data=' + this.doEncode(JSON.stringify(data));
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
