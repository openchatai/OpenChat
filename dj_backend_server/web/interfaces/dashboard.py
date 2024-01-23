from django.db.models import Count
from django.db.models.functions import TruncDay
from django.utils import timezone
from web.models.chat_histories import ChatHistory
from web.models.crawled_pages import CrawledPages
from web.models.pdf_data_sources import PdfDataSource
from web.models.website_data_sources import WebsiteDataSource


def get_discussion_counts():
    # Aggregate discussion counts per day grouped by session_id
    thirty_days_ago = timezone.now() - timezone.timedelta(days=30)
    discussion_counts = (
        ChatHistory.objects
        .filter(created_at__gte=thirty_days_ago)
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


def get_data_source_counts():
    # Aggregate data source counts per day
    thirty_days_ago = timezone.now() - timezone.timedelta(days=30)
    crawled_pages_counts = (
        CrawledPages.objects
        .filter(created_at__gte=thirty_days_ago)
        .annotate(created_date=TruncDay('created_at'))
        .values('created_date')
        .annotate(count=Count('id'))
        .order_by('created_date')
    )
    pdf_data_sources_counts = (
        PdfDataSource.objects
        .filter(created_at__gte=thirty_days_ago)
        .annotate(created_date=TruncDay('created_at'))
        .values('created_date')
        .annotate(count=Count('id'))
        .order_by('created_date')
    )
    website_data_sources_counts = (
        WebsiteDataSource.objects
        .filter(created_at__gte=thirty_days_ago)
        .annotate(created_date=TruncDay('created_at'))
        .values('created_date')
        .annotate(count=Count('id'))
        .order_by('created_date')
    )
    # Combine and format the counts
    all_dates = sorted(set(
        [cp['created_date'] for cp in crawled_pages_counts] +
        [pdf['created_date'] for pdf in pdf_data_sources_counts] +
        [ws['created_date'] for ws in website_data_sources_counts]
    ))
    formatted_counts = []
    for date in all_dates:
        formatted_counts.append({
            'date': str(date.date()),
            'crawled_pages': next((cp['count'] for cp in crawled_pages_counts if cp['created_date'] == date), 0),
            'pdf_data_sources': next((pdf['count'] for pdf in pdf_data_sources_counts if pdf['created_date'] == date), 0),
            'website_data_sources': next((ws['count'] for ws in website_data_sources_counts if ws['created_date'] == date), 0),
        })
    return formatted_counts

