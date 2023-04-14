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
You can do that by:
- Cloning this project
- Replace the excel sheet in src/sample/sample.xlsx
- Rebuild the container ```bin/build.sh``` 
- Run it ```bin/run.sh```

## How did progress go?
- I took some time to understand what was asked in the assessment. I was thrown off a bit by the Pearson correlation, that seemed daunting at first.
- Once I understood what the requested solution was, I started thinking about how the context can be logically organized that is clear to understand.
- I went back and forth a bit between class names, trying what felt like a natural fit.
- In real life, I would probably give this a few more passes. It feels very "mind dump / first attempt" right now.
- After 4.5 hours, the logic was fully functional on command line, using the fixed excel file on disk

## 3rd party libraries used
https://packagist.org/packages/php-ai/php-ml 
Used for calculating the Pearson Correlation

https://packagist.org/packages/phpoffice/phpspreadsheet
Used for importing data from Excel sheet

## Next steps?
- My idea was to create a controller for uploading the excel file and get a pretty presentation of the results in the browser.
  I would have stored the excel file in a temporary dir and would've simply used Bootstrap for styling.
- I would have liked to do some testing on larger data sets to see what limits there are and whether I need to load data in chunks.
- I would have liked to put the whole thing in a running docker environment somewhere, added a Nginx webserver in front of it.
- As an alternative to web based interface, I might have opted to update the loaded excel sheet by adding a worksheet called 'results' or something.
- Also, i've not taken the time to do any linting, that would probably smooth things out some more
- Unit tests would have been nice :) Where is the time...


## Result bin/run.sh
In case you're running into trouble getting this to run, in ```src/sample/result-output.txt``` you can see what the result would look like with the included sample data.