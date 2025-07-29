from zeep import Client

wsdl = "file:///C:/ArbiterSite/config/classes/rcc/rccservice.wsdl"
client = Client(wsdl)

for service in client.wsdl.services.values():
    for port in service.ports.values():
        operations = sorted(port.binding._operations.values(), key=lambda op: op.name)
        print(f"Port: {port.name}")
        for operation in operations:
            print(f"  Operation: {operation.name}")
