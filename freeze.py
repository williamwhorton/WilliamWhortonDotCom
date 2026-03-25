from app import create_app
from flask_frozen import Freezer

app = create_app('build')
freezer = Freezer(app)

with app.app_context():
    freezer.freeze()

