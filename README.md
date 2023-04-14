# Test Grader
Symfony command line tool in a Docker container that grades test results, as well as provides feedback on the quality of the questions.

## How to use
There are 3 bash scripts available in this project

```bin/build.sh``` 
Creates a local build of the image

```bin/run.sh```
Executes the grading of the exam results as stored in src/sample/sample.xlsx

```bin/login.sh```
Useful for development. Bind mounts the src dir into the container and starts a bash session
Running the script manual, run:
```bin/console app:test -vvv```

## I want to test this with a different excel file!
Simply replace the excel sheet in src/sample/sample.xlsx, as I've not had taken time to make it an argument in the Command or interface it.

## How did progress go?
- I took some time to understand what was asked in the assessment. I was thrown off a bit by the Pearson correlation, that seemed daunting at first.
- Once I understood what the requested solution was, I started thinking about how the context can be logically organized that is clear to understand.
- In real life, I would probably give this a few more passes. It feels rough to me right now.
- After 4.5 hours, the logic was full functional on command line and with a fixed excel file on disk

## 3rd party tools used
https://packagist.org/packages/php-ai/php-ml 
Used for calculating the Pearson Correlation

https://packagist.org/packages/phpoffice/phpspreadsheet
Used for importing data from Excel sheet

## Next steps?
- My idea was to create a controller for uploading the excel file and get a pretty presentation of the results in the browser.
  I would have stored the excel file in a temporary dir and would've simply used Bootstrap for styling.
- I would do some testing on larger data sets to see what limits there are and whether I need to load data in chunks. But I guess for exams of this magnitude it should be okay.
- I would have liked to put the whole thing in a running docker environment, added a Nginx webserver in front of it.
- As an alternative to web based, I could have created a new excel sheet, or update the one that was uploaded with a second worksheets called 'results' or something.
- Also, i've not taken the time to do any linting
- Unit tests would have been nice :) Where is the time...