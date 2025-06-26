#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

pull "satellite" "compiler/"

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