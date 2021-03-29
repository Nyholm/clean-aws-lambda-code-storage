# Clean AWS Lambda code storage

IF you have too many versions stored of your Lambda function. You will get the following
error:

> Code storage limit exceeded. (Service: AWSLambdaInternal; Status Code: 400; Error Code: CodeStorageExceededException;)

This means you need to remove some of these old versions to be able to deploy a
new one.


This repository fills the same purpose as https://github.com/epsagon/clear-lambda-storage,
but is written in PHP.