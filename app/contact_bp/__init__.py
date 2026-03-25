
from flask import Blueprint, render_template
from flask_wtf import FlaskForm
from wtforms import StringField, TextAreaField, SubmitField
from wtforms.validators import DataRequired, Email


contact_bp = Blueprint('contact', __name__)

class ContactForm(FlaskForm):
    name = StringField('Name', validators=[DataRequired()])
    email = StringField('Email', validators=[DataRequired(), Email()])
    message = TextAreaField('Message', validators=[DataRequired()])
    submit = SubmitField('Send')

@contact_bp.route('/contact/')
def contact():
    form = ContactForm()
    return render_template('contact/index.html', form=form)