# Introduction

This is a Jekyll theme for project documentation on github pages.  It was originally written for the [machete-php](machetephp.com) project.

## Notable Features

- Uses [repository metadata](https://help.github.com/articles/repository-metadata-on-github-pages/) to pull in your project information automatically.
- Multiple syntax highlighting themes to choose from.
- Fully responsive design.
- 100% compatible with the github pages build environment.
- No javascript.

## Demo

You can view a demo hosted on github pages [here](http://mattallan.org/parang/).

# Local Installation

## Jekyll Setup

First you need to install the [Jekyll requirements](http://jekyllrb.com/docs/installation/#requirements).  This theme runs on Jekyll 3 so you don't need node or python installed.

## Create a github pages branch

Github requires your docs to be in a special branch called `gh-pages`.  The following commands will create an orphan branch and remove all of the files from it.

```bash
cd your-local-project-checkout
git checkout --orphan gh-pages
git rm -rf .
```

## Install the theme

To install the theme, you should copy all of the files into your gh-pages repo and commit them.  The following commands will copy everything but the .git history of this theme.

```bash
curl -sL https://github.com/matthew-james/parang/archive/master.tar.gz | tar xz
cp -r parang-master/* ./
rm -fr parang-master
git add .
git commit -m 'init parang theme'
```

Once you have all the files, you need to install the dependencies.  The following command will install the ruby dependencies you need to exactly mirror the github pages setup locally:

```
bundle install
```

## Running Jekyl

Now that everything is installed, you can run jekyll like normal.  The following command will start a local development server and automatically rebuild your site when any changes are made.

```bash
bundle exec jekyll serve --watch
```

# Usage

You need to set the repository name in [_config.yml](_config.yml).  You can optionally change the color scheme for code highlighting.

If you want to use a color scheme that isn't included, any jekyll pygments or rouge css themes should work.

Documentation should be written as markdown or html files in the document root.  To create a link to the documentation, add an entry to the [_data/menu.yml](_data/menu.yml) file.  The permalink should match the permalink in your documentation file's [from matter](http://jekyllrb.com/docs/frontmatter/).

# Credits

- [skeleton css](http://getskeleton.com/)
- [jekyll-pygments-themes](https://github.com/jwarby/jekyll-pygments-themes)
- [github-corners](http://tholman.com/github-corners/)
- [jekyll-github-metadata](https://github.com/jekyll/github-metadata)