from django.shortcuts import render
from django.contrib.auth.decorators import login_required
from django.core.paginator import Paginator
from web.models.failed_jobs import (
    FailedJob,
)  # Assuming the model for the failed_jobs table is named FailedJob


@login_required
def errors_check(request):
    if request.user.is_superuser:
        error_list = (
            FailedJob.objects.all()
            .order_by("-failed_at")
            .values("payload", "uuid", "exception", "failed_at")
        )
    else:
        error_list = (
            FailedJob.objects.filter(user=request.user)
            .order_by("-failed_at")
            .values("payload", "uuid", "exception", "failed_at")
        )

    paginator = Paginator(error_list, 25)  # Show 25 errors per page
    page_number = request.GET.get("page")
    errors = paginator.get_page(page_number)
    return render(
        request,
        "errors_check.html",
        {"errors": errors, "paginator": paginator, "page_obj": errors},
    )
