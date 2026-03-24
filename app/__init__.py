from flask import Flask
from flask_frozen import Freezer

from config import config

links = [
    {'name': 'Home', 'routeName': 'main.index'},
    {'name': 'Work', 'routeName': 'main.work'},
    {'name': 'Journal', 'routeName': 'main.journal'},
    {'name': 'About', 'routeName': 'main.about'},
    {'name': 'Contact', 'routeName': 'contact.contact'},

]


def create_app(config_name):
    app = Flask(__name__)
    freezer = Freezer(app)

    app.config.from_object(config[config_name])

    config[config_name].init_app(app)

    @app.context_processor
    def inject_links():
        return dict(links=links)

    from .main import main as main_blueprint
    from .contact_bp import contact_bp

    app.register_blueprint(contact_bp)
    app.register_blueprint(main_blueprint)

    freezer.freeze()

    return app
