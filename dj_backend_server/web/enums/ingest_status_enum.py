from enum import Enum

class IngestStatusType(Enum):
    SUCCESS = 'success'
    FAILED = 'failed'
    PENDING = 'pending'

    def __new__(cls, value):
        obj = object.__new__(cls)
        obj._value_ = value
        return obj

    @classmethod
    def get_labels(cls):
        return {
            cls.SUCCESS: 'Success',
            cls.FAILED: 'Failed',
            cls.PENDING: 'Pending',
        }

    def get_label(self):
        return self.get_labels()[self]

    @classmethod
    def get_values(cls):
        return [member.value for member in cls]

    @classmethod
    def is_valid(cls, value):
        return value in cls.get_values()

    def is_successful(self):
        return self == self.SUCCESS

    def is_failed(self):
        return self == self.FAILED

    def is_pending(self):
        return self == self.PENDING

    def __str__(self):
        return self.value

    def equals(self, other):
        if not isinstance(other, IngestStatusType):
            return False
        return self.value == other.value
