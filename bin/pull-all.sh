#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

pull "satellite" "compiler/"
pull "satellite-toolbox" "toolbox/"
pull "dockerfile" "dockerfile/"
pull "phpspec-extension" "phpspec-extension/"
pull "phpunit-extension" "phpunit-extension/"
pull "fast-map" "tools/fast-map/"
pull "fast-map-config" "tools/fast-map-config/"

pull_plugin akeneo
pull_plugin csv
pull_plugin fast-map
pull_plugin json
pull_plugin log
pull_plugin prestashop
pull_plugin shopify
pull_plugin spreadsheet
pull_plugin sql
pull_plugin sylius

pull_expression_functions string
pull_expression_functions array