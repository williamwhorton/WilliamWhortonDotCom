import json

from flask import Blueprint, render_template, flash, redirect, url_for
from flask_wtf import FlaskForm
from wtforms import StringField, TextAreaField, SubmitField
from wtforms.validators import DataRequired, Email
from urllib import request as urlrequest
from urllib.error import URLError, HTTPError

contact_bp = Blueprint('contact', __name__)

class ContactForm(FlaskForm):
    name = StringField('Name', validators=[DataRequired()])
    email = StringField('Email', validators=[DataRequired(), Email()])
    message = TextAreaField('Message', validators=[DataRequired()])
    submit = SubmitField('Send')

@contact_bp.route('/contact/', methods=['GET', 'POST'])
def contact():
    form = ContactForm()
    if form.validate_on_submit():
        api_url = 'https://williamwhorton.com/contact_api.php'

        payload = json.dumps({
            'name': form.name.data,
            'email': form.email.data,
            'message': form.message.data,
        }).encode('utf-8')

        try:
            req = urlrequest.Request(
                api_url,
                data=payload,
                headers={'Content-Type': 'application/json'},
                method='POST',
            )

            with urlrequest.urlopen(req, timeout=15) as response:
                result = json.loads(response.read().decode('utf-8'))

            if result.get('ok'):
                flash('Thank you for your message!', 'success')
                return redirect(url_for('contact.contact'))

            flash(result.get('error', 'Sorry, your message could not be sent.'), 'error')

        except (HTTPError, URLError, TimeoutError, ValueError):
            flash('Sorry, your message could not be sent right now.', 'error')

        return render_template('contact/index.html', form=form)
    return render_template('contact/index.html', form=form)