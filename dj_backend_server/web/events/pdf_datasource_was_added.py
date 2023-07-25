from django.dispatch import Signal

# Define a custom Django signal
pdf_data_source_added = Signal(providing_args=[
    'chatbot_id', 'pdf_data_source_id',
])

# This is just a placeholder for demonstration. You can replace it with your actual model for PdfDataSource.
class PdfDataSource:
    pass

# This function will be the Django signal receiver.
def add_pdf_data_source(sender, chatbot_id, pdf_data_source_id, **kwargs):
    # Add the PdfDataSource instance or perform any other necessary actions here.
    # For demonstration, we're just printing the details.
    print("PDF data source was added:")
    print("Chatbot ID:", chatbot_id)
    print("PdfDataSource ID:", pdf_data_source_id)

# Connect the receiver function to the signal
pdf_data_source_added.connect(add_pdf_data_source)

# # Emit the signal when a pdf data source is added
# pdf_data_source_added.send(sender=PdfDataSource, chatbot_id=chatbot_id,
#                             pdf_data_source_id=pdf_data_source_id)