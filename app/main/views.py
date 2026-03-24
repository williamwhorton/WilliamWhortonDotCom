from flask import render_template, current_app, Flask
from . import main


@main.route('/')
def index():
    return render_template('index.html')


@main.route('/about/')
def about():
    return render_template('about/index.html')


@main.route('/work/')
def work():
    projects = current_app.work_pages

    return render_template('work/index.html', projects=projects)


@main.route('/journal/')
def journal():
    articles = current_app.journal_pages

    return render_template('journal/index.html', articles=articles)


@main.route('/journal/<string:slug>')
def journal_article(slug):
    for page in current_app.journal_pages:
        if page.meta['slug'] == slug:
            article = page
            return render_template('journal/article.html', article=article)

    return render_template('404.html'), 404
