package ml.misosiru.statistics;

public class Product {

    public String id;
    public String name;
    public String description;
    public String image;
    public String price;

    @Override
    public String toString(){
        return "{" +
                "id='" + id + '\'' +
                ", name='" + name + '\'' +
                ", description='" + description + '\'' +
                ", image='" + image + '\'' +
                ", price='" + price + '\'' +
                '}';
    }

}
