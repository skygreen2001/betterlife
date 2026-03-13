# Git

## cheatsheet

- 忽略文件修改，如Gc.js
  ```
    * 标记文件为"假设未修改"
      git update-index --assume-unchanged <文件路径/文件名>

    * 之后对该文件的修改不会被Git检测到
      当你需要重新跟踪该文件时：
    git update-index --no-assume-unchanged <文件路径/文件名>
  ```