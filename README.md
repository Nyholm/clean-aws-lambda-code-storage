# Clean AWS Lambda code storage

If you have too many versions stored of your Lambda function. You will get the following
error:

> Code storage limit exceeded. (Service: AWSLambdaInternal; Status Code: 400; Error Code: CodeStorageExceededException;)

This means you need to remove some of these old versions to be able to deploy a
new one.

This is a small application that will help you remove old versions of your functions.
It is meant to be deployed once and then you forget about it.

## Use

```
git clone https://github.com/Nyholm/clean-aws-lambda-code-storage.git
cd clean-aws-lambda-code-storage
composer update
# Modify regions in serverless.yml
serverless deploy
```

## Additional notes

This repository fills the same purpose as [epsagon/clear-lambda-storage](https://github.com/epsagon/clear-lambda-storage),
but is written in PHP.