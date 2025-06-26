
merge () {
  name=$1
  prefix=$2
  url=git@github.com:php-etl/$name.git

  echo -e "\e[32mAdding \e[37;42m$url\e[32;49m to the list of repositories to merge\e[0m"

  git subtree add --prefix "$prefix" "$url" main --squash
}

merge_plugin () {
  name=$1

  merge "$name-plugin" "plugins/$name/"
}

merge_expression_functions () {
  name=$1

  merge "$name-expression-language" "expression-language/$name/"
}

pull () {
  name=$1
  prefix=$2
  url=git@github.com:php-etl/$name.git

  echo -e "\e[32mPulling changes from \e[37;42m$url\e[32;49m into \e[37;42m$prefix\e[0m"

  git subtree pull --prefix "$prefix" "$url" main --squash
}

pull_plugin () {
  name=$1

  pull "$name-plugin" "plugins/$name/"
}

pull_expression_functions () {
  name=$1

  pull "$name-expression-language" "expression-language/$name/"
}

push () {
  name=$1
  prefix=$2
  url=git@github.com:php-etl/$name.git

  echo -e "\e[32mPulling changes from \e[37;42m$url\e[32;49m into \e[37;42m$prefix\e[0m"

  git subtree push --prefix "$prefix" "$url" main
}

push_plugin () {
  name=$1

  push "$name-plugin" "plugins/$name/"
}

push_expression_functions () {
  name=$1

  push "$name-expression-language" "expression-language/$name/"
}