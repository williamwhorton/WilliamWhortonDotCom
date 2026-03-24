import os

from flask_flatpages import FlatPages

basedir = os.path.abspath(os.path.dirname(__file__))

class Config:
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'you-will-never-guess'
    FLATPAGES_JOURNAL_AUTO_RELOAD = True
    FLATPAGES_JOURNAL_EXTENSION = '.md'
    FLATPAGES_JOURNAL_MARKDOWN_EXTENSIONS = ['codehilite']
    FLATPAGES_JOURNAL_MARKDOWN_EXTENSION_CONFIGS = {
        'codehilite': {'css_class': 'highlight'}
    }
    FLATPAGES_JOURNAL_ROOT = 'content/journal'

    FLATPAGES_WORK_AUTO_RELOAD = True
    FLATPAGES_WORK_EXTENSION = '.md'
    FLATPAGES_WORK_MARKDOWN_EXTENSIONS = ['codehilite']
    FLATPAGES_WORK_MARKDOWN_EXTENSION_CONFIGS = {
        'codehilite': {'css_class': 'highlight'}
    }
    FLATPAGES_WORK_ROOT = 'content/work'

    FREEZER_DESTINATION = 'build'

    @staticmethod
    def init_app(app):
        app.journal_pages = FlatPages(app, name="journal")
        app.work_pages = FlatPages(app, name="work")

class DevelopmentConfig(Config):
    DEBUG = True


config = {
    'development': DevelopmentConfig,

    'default': DevelopmentConfig
}