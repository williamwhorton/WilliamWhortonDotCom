from flask import render_template
from . import main

links = [
    {'name': 'Home', 'routeName': '.index'},
    {'name': 'About', 'routeName': '.about'},
]


@main.context_processor
def inject_links():
    return dict(links=links)


@main.route('/')
def index():
    return render_template('index.html')


@main.route('/about')
def about():
    return render_template('about/index.html', text='This is the about page')
