import os

from flask_flatpages import FlatPages

basedir = os.path.abspath(os.path.dirname(__file__))

class Config:
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'you-will-never-guess'
    FLATPAGES_AUTO_RELOAD = True
    FLATPAGES_EXTENSION = '.md'
    FLATPAGES_MARKDOWN_EXTENSIONS = ['codehilite']
    FLATPAGES_MARKDOWN_EXTENSION_CONFIGS = {
        'codehilite': {'css_class': 'highlight'}
    }
    FLATPAGES_ROOT = 'content'

    @staticmethod
    def init_app(app):
        app.pages = FlatPages(app)

class DevelopmentConfig(Config):
    DEBUG = True


config = {
    'development': DevelopmentConfig,

    'default': DevelopmentConfig
}