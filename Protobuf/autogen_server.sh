#!/bin/sh

export PATH="$PATH:$HOME/.local/bin" &&
cd "./Protobuf" &&
find . -name '*.proto' -exec protoc --plugin=protoc-gen-grpc=/usr/local/bin/protoc-gen-php-grpc --grpc_out=. {} \;
