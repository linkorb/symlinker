Symlinker
=========

Creates or updates symlinks from a config file

## Use-cases:

* Develop packages locally (composer, npm, ansible-galaxy)

## Installation:

```sh
$ git clone git@github.com:linkorb/symlinker.git
$ cd symlinker
$ composer install
```

Then update your `PATH` environment to include the `bin/` directory of the symlinker repository.

## Usage:

Create a file called `symlinker.yaml` in the root of your project, for example:

```yaml
links:
    - "~/git/my-package:./packages/my-package"
    - "../my-content:./my-content"
```

Your `symlinker.yaml` file is specific for your development environment, so be sure to add it to your `~/.gitignore` (in your home-dir, not `./gitignore` in the repo!)

Then you can quickly (re)install the symlinks:

```sh
$ symlinker link
```

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
