from flask import Flask, render_template

app = Flask(__name__)


links = [
    {'name': 'Home', 'routeName': 'index'},
    {'name': 'About', 'routeName': 'about'},
]

@app.context_processor
def inject_links():
    return dict(links=links)


@app.route('/')
def index():
    return render_template('index.html')


@app.route('/about')
def about():
    return render_template('about/index.html', text='This is the about page')


@app.errorhandler(404)
def page_not_found(e):
    return render_template('404.html', error=e), 404


@app.errorhandler(500)
def internal_server_error(e):
    return render_template('500.html', error=e), 500


if __name__ == '__main__':
    app.run()
