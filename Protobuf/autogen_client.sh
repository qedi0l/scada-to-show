#!/bin/sh

export PATH="$PATH:$HOME/.local/bin" &&
cd "./Protobuf" &&
find . -name '*.proto' -exec protoc --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin --proto_path=. --php_out=. --grpc_out=. {} \;
