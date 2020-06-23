'use strict';

const php = {
  render(template, data) {
    let cmd = 'php ' + template;
    let exec = require('child_process').exec;
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
