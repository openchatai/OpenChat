import logging
import traceback


def error_handler(func):
    def wrapper(*args, **kwargs):
        try:
            return func(*args, **kwargs)
        except Exception as e:            
            print(e)
            traceback.print_exc()
            # Log the error trace to the terminal
            logging.exception(f"Error occurred in function {func.__name__}: {str(e)}")
            raise  # Re-raise the exception after logging