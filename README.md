gRPC Server Bundle
-------------------

Create gRPC server using [spriral/php-grpc](https://spiral.dev/docs/grpc-configuration)

Installation
============

### Step 1: Download the bundle
```console
$ composer require playtini/grpc-server-bundle
```

### Step 2: Copy server binary to `./project/bin` folder
```console
./vendor/playtini/grpc-server-bundle/bin/rr-grpc ./project/bin/
```

### Step 3: Create server config `.rr.yaml` in project root folder:
```yaml
grpc:
  listen: "tcp://:6096"
  proto: "./proto/base.proto"
  workers:
    command: "./bin/console roadrunner:grpc-worker"
    relay: "unix://var/roadrunner.sock"
    pool:
      numWorkers: 1

limit:
  interval: 1
  services:
    grpc:
      maxMemory: 100
      TTL: 0
      idleTTL: 0
      execTTL: 60
```
## Usage example

#### Create *.proto
base.proto
```console
syntax="proto3";

package playtini;

option php_generic_services = true;
option php_namespace = "Playtini\\MainServiceName";
option php_metadata_namespace = "Playtini\\MainServiceName\\Meta";

import 'calculator.proto';
```

calculator.proto
```console
syntax = "proto3";

package playtini.calculator;

option php_namespace = "Playtini\\MainServiceName\\Calculator";
option php_metadata_namespace = "Playtini\\MainServiceName\\Meta";

message Sum {
  int32 a = 1;
  int32 b = 2;
}

message Result {
  int32 result = 1;
}

service Calculator {
  rpc Sum (calculator.Sum) returns (calculator.Result);
}
```

#### Generate service
Generate service using `protoc` and `protoc-gen-grpc` plugin(roadrunner custom plugin):
```console
protoc /proto/base.proto \
        -I/proto -I/proto/base.proto proto/calculator.proto \
        --php_out=/proto/src \
        $(: 👇 custom plugin from roadrunner to generate server interface) \
        --php-grpc_out=/proto/src \
        $(: 👇 generates the client code) \
        --grpc_out=/proto/src \
        --plugin=protoc-gen-grpc=/protobuf/grpc/bins/opt/grpc_php_plugin \
        --proto_path /proto
```

#### Create server side code
Implement generated interface:
```php
<?php

namespace App\Calculator;

use Playtini\MainServiceName\Calculator\CalculatorInterface;
use Playtini\MainServiceName\Calculator\Result;
use Playtini\MainServiceName\Calculator\Sum;
use Spiral\GRPC;

class CalculatorService implements CalculatorInterface
{
    public function Sum(GRPC\ContextInterface $ctx, Sum $in): Result
    {
        return new Result([
            'result' => $in->getA() + $in->getB(), 
        ]);
    }
}
```
Add to `service.yaml`:
```yaml
services:
    App\Calculator\CalculatorService:
        tags: ['playtini.roadrunner.grpc_service']
```
Run the server:
```console
bin/rr-grpc serve -v -d
```

### Help
[spriral/php-grpc](https://github.com/spiral/php-grpc) - high-performance PHP GRPC server build at top of RoadRunner.

### Related articles and source
- [Building gRPC server](https://dev.to/khepin/building-a-grpc-server-in-php-3bgc)
- [RoadRunner gRPC](https://medium.com/@victor_nerd/roadrunner-hybrid-php-golang-intro-9cc562fb4a09)
- [roadrunner-bundle](https://github.com/Baldinof/roadrunner-bundle) - thank `vsychov` for cool pool request that add gRPC server support