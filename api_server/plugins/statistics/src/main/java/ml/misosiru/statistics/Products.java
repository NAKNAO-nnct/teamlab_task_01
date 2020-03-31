package ml.misosiru.statistics;

import java.util.List;

public class Products {
    public String success;
    public String message;
    public List<Product> details;
    public String details_url;

    public String getSuccess(){
        return success;
    }

    public void setSuccess(String success){
        this.success = success;
    }

    public String getMessage(){
        return message;
    }

    public void setMessage(String message){
        this.message = message;
    }

    public List<Product> getDetails(){
        return details;
    }

    public void setDetails(List<Product> details){
        this.details = details;
    }

    public String getDetails_url(){
        return details_url;
    }

    public void setDetails_url(String details_url){
        this.details_url = details_url;
    }

    @Override
    public String toString(){
        return "{" +
                "success='" + success + '\'' +
                ", message='" + message + '\'' +
                ", details=" + details +
                ", details_url='" + details_url + '\'' +
                '}';
    }

}
