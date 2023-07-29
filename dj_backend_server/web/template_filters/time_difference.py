from django import template
from datetime import datetime, timedelta

register = template.Library()

@register.filter
def time_difference(value):
    if not value:
        return ""
    
    now = datetime.now(value.tzinfo)
    diff = now - value
    return diff.total_seconds() / 60  # Return the time difference in minutes


