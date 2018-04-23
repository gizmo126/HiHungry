"#!/usr/bin/env python"

import json
import sys

try:
    data = json.loads(sys.argv[1])
except:
    print("ERROR")
    sys.exit(1)

result = data

print(json.dumps(sys.path))
