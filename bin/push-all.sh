#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

push "satellite" "compiler/"
push "satellite-toolbox" "toolbox/"
push "dockerfile" "dockerfile/"
push "phpspec-extension" "phpspec-extension/"
push "phpunit-extension" "phpunit-extension/"
push "fast-map" "tools/fast-map/"
push "fast-map-config" "tools/fast-map-config/"

push_plugin akeneo
push_plugin csv
push_plugin fast-map
push_plugin json
push_plugin log
push_plugin prestashop
push_plugin shopify
push_plugin spreadsheet
push_plugin sql
push_plugin sylius

push_expression_functions string
push_expression_functions array