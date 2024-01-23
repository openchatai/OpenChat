from django.db.models import Count
from django.db.models.functions import TruncDay
from web.models.chat_histories import ChatHistory


def get_discussion_counts():
    # Aggregate discussion counts per day grouped by session_id
    discussion_counts = (
        ChatHistory.objects
        .values('session_id')
        .annotate(created_date=TruncDay('created_at'))
        .values('created_date')
        .annotate(discussion_count=Count('id'))
        .order_by('created_date')
    )
    formatted_counts = [
        {'year': count['created_date'].strftime('%b %d'), 'value': count['discussion_count']}
        for count in discussion_counts
    ]
    return formatted_counts


