#!/bin/bash
service mysql start
php app/console server:run 0.0.0.0:8000
