from typing import Optional

class StoreOptions:
    def __init__(self, namespace: Optional[str] = None):
        self.namespace = namespace