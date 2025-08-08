# in2glossar

This is a glossary extension for TYPO3

![Tooltip](Documentation/Images/screenshot_tooltip.png "Tooltip")

![Listview](Documentation/Images/screenshot_listview.png "Listview")

## Documentation

This is an extension especially built for the needs of one customer to show tooltips and a list view with all glossary
records.

## Contribution with ddev

This repository provides a [DDEV]()-backed development environment. If DDEV is installed, simply run the following
commands to quickly set up a local environment with example usages:

* `ddev start`
* `ddev initialize`

### Requirements

1. Install ddev, see: https://ddev.readthedocs.io/en/stable/#installation
2. Install git-lfs, see: https://git-lfs.github.com/

### Installation

1. Clone this repository
2. Run `ddev start`
3. Run `ddev initialize` to setup configurations and test database

### Branchinfo

* Main Branch - Next Major Version
* typo3-v13: Version V13 for TYPO3 13
* typo3-v12: Version V4 for TYPO3 12

## Early Access Programm for TYPO3 14 support

:information_source: **TYPO3 14 compatibility**
> See [EAP page (DE)](https://www.in2code.de/agentur/typo3-extensions/early-access-programm/) or
> [EAP page (EN)](https://www.in2code.de/en/agency/typo3-extensions/early-access-program/) for more information how
> to get access to a TYPO3 14 version

## Changelog

| Version | Date       | State   | Description                                      |
|---------|------------|---------|--------------------------------------------------|
| 13.0.0  | 2025-08-08 | Feature | TYPO3 v13 compatibility                          |
| 4.0.0   | 2024-07-16 | Feature | TYPO3 v12 compatibility                          |
| 3.0.0   | 2024-07-16 | Feature | TYPO3 v11 compatibility and modern markup option |
| 2.0.2   | 2021-11-18 | Bugfix  | Sort by word in backend list module              |
| 2.0.1   | 2021-10-01 | Bugfix  | Fix exception in backend module                  |
| 2.0.0   | 2021-07-09 | Task    | Update for TYPO3 10                              |
| 1.1.0   | 2021-03-03 | Task    | Don't use tooltips in A-tags                     |

## Contribution

Pull requests are welcome in general! Nevertheless, please don't forget to add a description to your pull requests. This is very helpful to understand what kind of issue the PR is going to solve.

* Bugfixes: Please describe what kind of bug your fix solve and give us feedback how to reproduce the issue. We're going to accept only bugfixes if I can reproduce the issue.
* Features: Not every feature is relevant for the bulk of in2glossar users.
