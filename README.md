PxProBoilerplate
================
Description
-----------
This is a boilerplate website using Node’s npm package manager as the build tool (vs Grunt, Gulp, Broccoli, et.al.)

Features
--------
It comes with SASS as the CSS pre-processor and Autoprefixer as the post-processor to handle vendor prefixes.

You can write ES6 (ES2015) Javascript and it will transpile via Babel.

Browser syncing (ala LiveReload) is handled by BrowserSync because it has a proxy so that you can use PHP and Virtual Hosts.

Installation
------------
First off, my apologies if someone is actually trying to follow these instructions, this repo is really intended just for me so the instructions are a bit terse; but if this helps anyone else, that’s awesome.

You will first need to install Node and npm. Once that is done, globally install a few packages if you don’t already have them. To check, run `npm list -g --depth=0`.

    npm install -g browser-sync
    npm install -g autoprefixer
    npm install -g postcss-cli
    npm install -g babel

Then get the local packages by running `npm install`.

Finally, on line 15 of package.json (the "reload" task), you will need to change the virtual host name from `boilerplate.dev` to your name.
