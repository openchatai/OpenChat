from enum import Enum


class WebsiteDataSourceStatusType(Enum):
    PENDING = 'pending'
    IN_PROGRESS = 'in_progress'
    COMPLETED = 'completed'
    FAILED = 'failed'

    def __new__(cls, value):
        obj = object.__new__(cls)
        obj._value_ = value
        return obj

    @classmethod
    def get_labels(cls):
        return {
            cls.PENDING: 'Pending',
            cls.IN_PROGRESS: 'In Progress',
            cls.COMPLETED: 'Completed',
            cls.FAILED: 'Failed',
        }

    def get_label(self):
        return self.get_labels()[self]

    @classmethod
    def get_values(cls):
        return [member.value for member in cls]

    @classmethod
    def is_valid(cls, value):
        return value in cls.get_values()

    def is_pending(self):
        return self == self.PENDING

    def is_in_progress(self):
        return self == self.IN_PROGRESS

    def is_completed(self):
        return self == self.COMPLETED

    def is_failed(self):
        return self == self.FAILED
